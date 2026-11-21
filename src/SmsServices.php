<?php
declare(strict_types=1);

/** Sends SMS and logs every message in the notifications table. */
class SmsService
{
    public function __construct(private PDO $db, private array $cfg) {}

    /** Convert a Ghanaian local number or an international number to E.164 format. */
    public static function toInternational(string $phone): string
    {
        $phone = preg_replace('/[\s().-]+/', '', trim($phone));
        if (!is_string($phone) || $phone === '') {
            throw new InvalidArgumentException('A valid phone number is required.');
        }

        if (preg_match('/^0\d{9}$/', $phone)) {
            return '+233' . substr($phone, 1);
        }
        if (preg_match('/^233\d{9}$/', $phone)) {
            return '+' . $phone;
        }
        if (preg_match('/^\+[1-9]\d{7,14}$/', $phone)) {
            return $phone;
        }

        throw new InvalidArgumentException('The phone number must be a valid Ghanaian or international number.');
    }

    public function send(int $patientId, string $phone, string $type, string $message, ?int $queueId = null): bool
    {
        $st = $this->db->prepare(
            "INSERT INTO notifications (patient_id, queue_id, type, message) VALUES (?,?,?,?)"
        );
        $st->execute([$patientId, $queueId, $type, $message]);
        $id = (int)$this->db->lastInsertId();

        $ok = false;
        $ref = null;
        $status = 'failed';

        try {
            $internationalPhone = self::toInternational($phone);
            if (($this->cfg['driver'] ?? '') === 'log') {
                $status = 'queued';
            } elseif (($this->cfg['driver'] ?? '') === 'arkesel') {
                [$ok, $ref] = $this->viaArkesel(
                    substr($internationalPhone, 1),
                    $message
                );
                $status = $ok ? 'sent' : 'failed';
            } else {
                error_log('[SmsService] SMS driver is missing or unsupported.');
            }
        } catch (InvalidArgumentException) {
            error_log('[SmsService] SMS was not sent because the recipient phone number is invalid.');
        }

        $up = $this->db->prepare(
            "UPDATE notifications SET delivery_status=?, provider_ref=?, sent_at=? WHERE notification_id=?"
        );
        $up->execute([$status, $ref, $ok ? date('Y-m-d H:i:s') : null, $id]);
        return $ok;
    }

    /** @return array{bool, ?string} */
    private function viaArkesel(string $to, string $message): array
    {
        $apiKey = trim((string)($this->cfg['api_key'] ?? ''));
        $senderId = trim((string)($this->cfg['sender_id'] ?? ''));
        if ($apiKey === '' || $senderId === '') {
            error_log('[SmsService] Arkesel API key or sender ID is not configured.');
            return [false, null];
        }
        if (!function_exists('curl_init')) {
            error_log('[SmsService] The PHP cURL extension is required to send SMS.');
            return [false, null];
        }

        $ch = curl_init('https://sms.arkesel.com/api/v2/sms/send');
        if ($ch === false) {
            error_log('[SmsService] Could not initialize the Arkesel request.');
            return [false, null];
        }

        $payload = json_encode([
            'sender' => $senderId,
            'message' => $message,
            'recipients' => [$to],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($payload === false) {
            error_log('[SmsService] Could not encode the Arkesel request.');
            return [false, null];
        }

        $configured = curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'api-key: ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => $payload,
        ]);
        if (!$configured) {
            curl_close($ch);
            error_log('[SmsService] Could not configure the Arkesel request.');
            return [false, null];
        }

        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            error_log('[SmsService] Arkesel transport error: ' . $curlError);
            return [false, null];
        }

        $data = json_decode($body, true);
        if ($code < 200 || $code >= 300 || !is_array($data) || ($data['status'] ?? null) !== 'success') {
            error_log('[SmsService] Arkesel rejected the SMS request (HTTP ' . $code . ').');
            return [false, null];
        }

        $recipient = $data['data'][0] ?? null;
        if (!is_array($recipient) || !isset($recipient['id']) || !is_string($recipient['id'])) {
            error_log('[SmsService] Arkesel returned success without a message reference.');
            return [false, null];
        }

        return [true, substr($recipient['id'], 0, 100)];
    }
}