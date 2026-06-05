<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler, bool $admin = false): void
    {
        $this->add('GET', $path, $handler, $admin);
    }

    public function post(string $path, callable $handler, bool $admin = false): void
    {
        $this->add('POST', $path, $handler, $admin);
    }

    public function put(string $path, callable $handler, bool $admin = false): void
    {
        $this->add('PUT', $path, $handler, $admin);
    }

    public function delete(string $path, callable $handler, bool $admin = false): void
    {
        $this->add('DELETE', $path, $handler, $admin);
    }

    private function add(string $method, string $path, callable $handler, bool $admin): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'admin');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = '/' . trim(rawurldecode($path), '/');
        $base = $this->detectBasePath();
        if ($base && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }
        // Strip /index.php if present
        if (str_ends_with($path, '/index.php')) {
            $path = dirname($path) ?: '/';
        }
        $uri = $path === '' ? '/' : $path;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $pattern = preg_replace('/\{([a-z_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            if ($route['admin']) {
                Auth::requireAdmin();
            }
            call_user_func($route['handler'], $params);
            return;
        }

        Response::error('Endpoint not found', 404);
    }

    private function detectBasePath(): string
    {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        return rtrim(dirname($script), '/');
    }
}
