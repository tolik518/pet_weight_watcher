<?php

namespace oszimt\petweightwatcher;

class Router
{
    public function __construct(
        private readonly PetCallbackHandler $petCallbackHandler
    ) {}

    public function getPage(): void
    {
        $this->addRoute('GET', '/', [], [$this->petCallbackHandler, 'getHealthCheck']);

        $this->addRoute('GET', '/pet', ['name'], [$this->petCallbackHandler, 'getOnePetTypeWithAllData']);
        $this->addRoute('POST', '/pet', [], [$this->petCallbackHandler, 'setPetData']);

        $this->addRoute('GET', '/pets', [], [$this->petCallbackHandler, 'getPetTypes']);
        $this->addRoute('GET', '/pets/all', [], [$this->petCallbackHandler, 'getAllPetTypesWithAllData']);
    }

    /**
     * @param string   $request_method GET, POST, PUT, DELETE, ...
     * @param string   $route          Bsp. /pet, /pets, /pets/all
     * @param array    $argKeys        Bsp. ['name', 'gewicht']
     * @param callable $callback       Die Methode die aufgerufen werden soll, wenn die Route passt
     *
     * @return void
     */
    private function addRoute(string $request_method, string $route, array $argKeys, callable $callback): void
    {
        $argValues = $this->getArgValues($argKeys);
        if ($_SERVER['REQUEST_METHOD'] == $request_method && $this->getUri() == $route) {
            // Überprüfe, ob alle Pflicht-Parameter vorhanden sind
            if ($argValues === null) {
                return;
            }
            $callback(...$argValues);
        }
    }

    /**
     * @return string Gibt nur den Uri-Teil der Anfrage zurück
     *                Bsp. /pet?name=Hund&gewicht=10.5 returns /pet
     */
    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri)[0];
        return $uri;
    }

    /**
     * @param array $argKeys Bsp. ['name', 'gewicht']
     *
     * @return array|null Gibt ein Array mit den Werten der Argumente zurück, ansonsten null
     */
    private function getArgValues(array $argKeys): ?array {
        $argValues = [];
        foreach ($argKeys as $argKey) {
            if (!isset($_GET[$argKey])) {
                // Wenn einer der Pflicht-Parameter fehlt, dann breche ab
                return null;
            }
            $argValues[] = $_GET[$argKey];
        }
        return $argValues;
    }
}

