<?php

namespace BeautyCoding\Revision\Revisions;

use BeautyCoding\Revision\Contracts\TargetResolver;
use BeautyCoding\Revision\Models\Revision;

class BaseResolver implements TargetResolver
{
    /**
     * Revision
     * @var \BeautyCoding\Revision\Models\Revision
     */
    private $revision;

    public function __construct(Revision $revision)
    {

        $this->revision = $revision;
    }

    /**
     * Main function
     * @return array
     */
    public function get()
    {
        $response = [];

        foreach ($this->revision->decoded_before as $key => $value) {
            $function = camel_case($key);
            if (method_exists($this, $function)) {
                $response[] = $this->$function($value, $this->revision->decoded_after->$key);
            } else {
                $tmp = new \stdClass();

                $tmp->name = $key;
                $tmp->before = $value;
                $tmp->after = $this->revision->decoded_after->$key;

                $response[] = $tmp;
            }
        }

        return $response;
    }
}
