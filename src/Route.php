<?php

namespace ApiDocGenerator;

class Route
{
    private $method;
    private $path;
    private $title;
    private $description;
    private $headers = [];
    private $queryParams = [];
    private $pathParams = [];
    private $bodyParams = [];
    private $responses = [];
    private $authRequired = false;
    private $authType = null;
    private $authDescription = null;

    public function __construct(string $method, string $path)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function addHeader(string $name, string $type, bool $required = false, string $description = ''): self
    {
        $this->headers[] = [
            'name' => $name,
            'type' => $type,
            'required' => $required,
            'description' => $description
        ];
        return $this;
    }

    public function addQueryParam(string $name, string $type, bool $required = false, string $description = '', $default = null): self
    {
        $this->queryParams[] = [
            'name' => $name,
            'type' => $type,
            'required' => $required,
            'description' => $description,
            'default' => $default
        ];
        return $this;
    }

    public function addPathParam(string $name, string $type, string $description = ''): self
    {
        $this->pathParams[] = [
            'name' => $name,
            'type' => $type,
            'description' => $description
        ];
        return $this;
    }

    public function addBodyParam(string $name, string $type, bool $required = false, string $description = '', $example = null): self
    {
        $this->bodyParams[] = [
            'name' => $name,
            'type' => $type,
            'required' => $required,
            'description' => $description,
            'example' => $example
        ];
        return $this;
    }

    public function addResponse(int $statusCode, string $description, array $example = null): self
    {
        $this->responses[] = [
            'statusCode' => $statusCode,
            'description' => $description,
            'example' => $example
        ];
        return $this;
    }

    public function requireAuth(string $type = 'Bearer', string $description = ''): self
    {
        $this->authRequired = true;
        $this->authType = $type;
        $this->authDescription = $description;
        return $this;
    }

    public function toMarkdown(): string
    {
        $md = "## {$this->title}\n\n";
        
        if ($this->description) {
            $md .= "{$this->description}\n\n";
        }

        $md .= "**Endpoint:** `{$this->method} {$this->path}`\n\n";

        // Authentication
        if ($this->authRequired) {
            $md .= "### Authentication\n\n";
            $md .= "This endpoint requires authentication.\n\n";
            $md .= "- **Type:** {$this->authType}\n";
            if ($this->authDescription) {
                $md .= "- **Description:** {$this->authDescription}\n";
            }
            $md .= "\n";
        }

        // Headers
        if (!empty($this->headers) || $this->authRequired) {
            $md .= "### Headers\n\n";
            $md .= "| Name | Type | Required | Description |\n";
            $md .= "|------|------|----------|-------------|\n";
            
            if ($this->authRequired) {
                $md .= "| Authorization | string | Yes | {$this->authType} token |\n";
            }
            
            foreach ($this->headers as $header) {
                $required = $header['required'] ? 'Yes' : 'No';
                $md .= "| {$header['name']} | {$header['type']} | {$required} | {$header['description']} |\n";
            }
            $md .= "\n";
        }

        // Path Parameters
        if (!empty($this->pathParams)) {
            $md .= "### Path Parameters\n\n";
            $md .= "| Name | Type | Description |\n";
            $md .= "|------|------|-------------|\n";
            foreach ($this->pathParams as $param) {
                $md .= "| {$param['name']} | {$param['type']} | {$param['description']} |\n";
            }
            $md .= "\n";
        }

        // Query Parameters
        if (!empty($this->queryParams)) {
            $md .= "### Query Parameters\n\n";
            $md .= "| Name | Type | Required | Default | Description |\n";
            $md .= "|------|------|----------|---------|-------------|\n";
            foreach ($this->queryParams as $param) {
                $required = $param['required'] ? 'Yes' : 'No';
                $default = $param['default'] !== null ? $param['default'] : '-';
                $md .= "| {$param['name']} | {$param['type']} | {$required} | {$default} | {$param['description']} |\n";
            }
            $md .= "\n";
        }

        // Body Parameters
        if (!empty($this->bodyParams)) {
            $md .= "### Request Body\n\n";
            $md .= "| Name | Type | Required | Description | Example |\n";
            $md .= "|------|------|----------|-------------|----------|\n";
            foreach ($this->bodyParams as $param) {
                $required = $param['required'] ? 'Yes' : 'No';
                $example = $param['example'] !== null ? $param['example'] : '-';
                $md .= "| {$param['name']} | {$param['type']} | {$required} | {$param['description']} | {$example} |\n";
            }
            $md .= "\n";

            // Example Request Body
            $md .= "**Example Request:**\n\n";
            $md .= "```json\n";
            $exampleBody = [];
            foreach ($this->bodyParams as $param) {
                if ($param['example'] !== null) {
                    $exampleBody[$param['name']] = $param['example'];
                }
            }
            $md .= json_encode($exampleBody, JSON_PRETTY_PRINT);
            $md .= "\n```\n\n";
        }

        // Responses
        if (!empty($this->responses)) {
            $md .= "### Responses\n\n";
            foreach ($this->responses as $response) {
                $md .= "#### {$response['statusCode']} - {$response['description']}\n\n";
                if ($response['example']) {
                    $md .= "```json\n";
                    $md .= json_encode($response['example'], JSON_PRETTY_PRINT);
                    $md .= "\n```\n\n";
                }
            }
        }

        $md .= "---\n\n";

        return $md;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTitle(): string
    {
        return $this->title ?? "{$this->method} {$this->path}";
    }
}