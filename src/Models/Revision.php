<?php

namespace BeautyCoding\Revision\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'before', 'after',
    ];

    protected $revisions = [
    ];

    /**
     * User relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Decoded before data
     * @return stdClass
     */
    public function getDecodedBeforeAttribute()
    {
        if (!$this->decoded_before_object) {
            $this->decoded_before_object = json_decode($this->before, false);
        }

        return $this->decoded_before_object;
    }

    /**
     * Decoded after data
     * @return stdClass
     */
    public function getDecodedAfterAttribute()
    {
        if (!$this->decoded_after_object) {
            $this->decoded_after_object = json_decode($this->after, false);
        }

        return $this->decoded_after_object;
    }

    /**
     * All translated changes
     * @return array
     */
    public function getChangesAttribute()
    {
        if (config(sprintf('revision.%s', $this->target_type))) {
            return (new $this->revisions[$this->target_type]($this))->get();
        }

        return (new BaseResolver())->get();
    }

}
