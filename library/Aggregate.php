<?php

interface Aggregate
{
    public function handle(Command $command);

    public function apply(Event $event);
}