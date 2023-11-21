<?php

namespace oszimt\petweightwatcher;

class Router
{
    public function __construct(
        private PetCallbackHandler $petCallbackHandler
    ) {}

    public function getPage()
    {
        $this->addRoute('GET', '/pet', ['name', 'gewicht'], [$this->petCallbackHandler, 'getPetAverageWeight']);
        $this->addRoute('GET', '/pets', [], [$this->petCallbackHandler, 'getPetTypes']);
        $this->addRoute('GET', '/pets/all', [], [$this->petCallbackHandler, 'getPetTypesWithAllData']);
    }

    /**
     * @param string   $request_method GET, POST, PUT, DELETE, ...
     * @param string   $route          ex. /pet, /pets, /pets/all
     * @param array    $argKeys        ex. ['name', 'gewicht']
     * @param callable $callback       Method that should be called if the route matches
     *
     * @return void
     */
    private function addRoute(string $request_method, string $route, array $argKeys, callable $callback): void
    {
        $argValues = $this->getArgValues($argKeys);

        if ($_SERVER['REQUEST_METHOD'] == $request_method && $this->getUri() == $route) {
            // Check if any arguments are missing
            if ($argValues === null) {
                echo json_encode(['error' => 'Bitte geben Sie alle Argumente an: ' . implode(', ', $argKeys)]);
                return;
            }
            $callback(...$argValues);
        }
    }

    /**
     * @return string Returns only the Uri part from the request
     *                ex. /pet?name=Hund&gewicht=10.5 returns /pet
     */
    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri)[0];
        return $uri;
    }

    /**
     * @param array $argKeys ex. ['name', 'gewicht']
     *
     * @return array|null Returns an array with the values of the arguments, otherwise returns null
     */
    private function getArgValues(array $argKeys): ?array {
        $argValues = [];
        foreach ($argKeys as $argKey) {
            if (!isset($_GET[$argKey])) {
                return null; // If a required argument is missing, return null, so the route doesn't match
            }
            $argValues[] = $_GET[$argKey];
        }
        return $argValues;
    }
}

