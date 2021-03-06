<?php namespace MaddHatter\LaravelFullcalendar;

use Illuminate\Support\Collection;

class EventCollection
{

    /**
     * @var Collection
     */
    protected $events;

    public function __construct()
    {
        $this->events = new Collection();
    }

    public function merge($events)
    {
        $this->events = $this->events->merge($events);
    }

    public function push(Event $event, array $customAttributes = [])
    {
        if(!empty($customAttributes)) {
            $this->events->push($this->convertToArray($event, $customAttributes));
        } else {
            $this->events->push($event);
        }
    }

    public function toJson()
    {
        return $this->events->toJson();
    }

    public function toArray()
    {
        return $this->events->toArray();
    }
    
    private function convertToArray(Event $event, array $customAttributes = [])
    {
        return array_merge([
            'id' => $this->getEventId($event),
            'title' => $event->getTitle(),
            'allDay' => $event->isAllDay(),
            'start' => $event->getStart()->format('c'),
            'end' => $event->getEnd()->format('c'),
        ], $customAttributes);
    }

    private function getEventId(Event $event)
    {
        if ($event instanceof IdentifiableEvent) {
            return $event->getId();
        }

        return null;
    }
}