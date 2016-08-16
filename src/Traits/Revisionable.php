<?php

namespace BeautyCoding\Revision\Traits;

use BeautyCoding\Revision\Models\Revision;
use Illuminate\Support\Facades\Auth;

trait Revisionable
{
    public static function bootRevisionable()
    {
        static::updating(function ($model) {
            $model->review();
        });
    }

    /**
     * Revisions morph
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function revisions()
    {
        return $this->morphMany(Revision::class, 'target')->latest();
    }

    /**
     * Apply revision on model
     * @param  int|null   $userId
     * @param  array|null $diff
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function review(int $userId = null, array $diff = null)
    {
        $userId = $userId ?: Auth::id();
        $diff = $diff ?: $this->getDiff();

        if ($diff) {
            return $this->revisions()->create(array_merge(['user_id' => $userId], $diff));
        }
    }

    /**
     * Model changes diff
     * @return array
     */
    protected function getDiff(): array
    {
        $changed = $this->getDirty();

        if ($this->observe) {
            $changed = array_intersect_key($changed, array_flip($this->observe));
        }

        if (!$changed) {
            return [];
        }

        $before = json_encode(array_intersect_key($this->getOriginal(), $changed));
        $after = json_encode($changed);

        return compact('before', 'after');
    }

}
