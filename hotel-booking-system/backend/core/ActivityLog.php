<?php
/**
 * Records every significant action so admin and customer panels stay in sync.
 */
class ActivityLog
{
    public static function record(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
        ?int $actorId = null,
        ?string $actorRole = null
    ): void {
        try {
            $user = $actorId ? null : Auth::user();
            Database::connection()->prepare(
                'INSERT INTO activity_log (actor_id, actor_role, action, entity_type, entity_id, description)
                 VALUES (?, ?, ?, ?, ?, ?)'
            )->execute([
                $actorId ?? ($user['id'] ?? null),
                $actorRole ?? ($user['role'] ?? null),
                $action,
                $entityType,
                $entityId,
                $description,
            ]);
        } catch (Throwable $e) {
            // Non-fatal — never break the main flow
        }
    }
}
