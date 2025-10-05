<?php 

namespace ApiDocGenerator; 

class ApiDocGenerator{

    private $routes = [];
    private $title = 'API Documentation';
    private $description = '';

    private $version = '1.0.0';

    private $baseUrl = '';

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function setVersion($version){
        $this->version = $version;
        return $this;
    }

    public function setBaseUrl($baseUrl){
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

        public function addRoute(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function generate(string $outputPath = 'api-documentation.md'): bool
    {
        $markdown = $this->generateMarkdown();
        
        // Ensure directory exists
        $directory = dirname($outputPath);
        if (!is_dir($directory) && $directory !== '.') {
            mkdir($directory, 0755, true);
        }

        $result = file_put_contents($outputPath, $markdown);
        
        return $result !== false;
    }

    private function generateMarkdown(): string
    {
        $md = "# {$this->title}\n\n";
        
        if ($this->description) {
            $md .= "{$this->description}\n\n";
        }

        $md .= "**Version:** {$this->version}\n\n";

        if ($this->baseUrl) {
            $md .= "**Base URL:** `{$this->baseUrl}`\n\n";
        }

        $md .= "---\n\n";

        // Table of Contents
        if (!empty($this->routes)) {
            $md .= "## Table of Contents\n\n";
            foreach ($this->routes as $index => $route) {
                $anchor = $this->createAnchor($route->getTitle());
                $md .= ($index + 1) . ". [{$route->getTitle()}](#{$anchor})\n";
            }
            $md .= "\n---\n\n";
        }

        // Routes
        foreach ($this->routes as $route) {
            $md .= $route->toMarkdown();
        }

        // Footer
        $md .= "\n---\n\n";
        $md .= "*Documentation generated on " . date('Y-m-d H:i:s') . "*\n";

        return $md;
    }

    private function createAnchor(string $text): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
