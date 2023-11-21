<?php

namespace oszimt\petweightwatcher;

class Factory
{
    public function createRouter(): Router {
        return new Router(
            $this->createPetCallbackHandler()
        );
    }

    private function createPetCallbackHandler(): PetCallbackHandler {
        return new PetCallbackHandler(
            new DataReader(),
            new DataWriter()
        );
    }
}