[ollama/ollama](https://github.com/ollama/ollama)

# Building

## Running local builds

Next, start the server:

```shell
ollama serve
```

Finally, in a separate shell, run a model:

```shell
ollama run llama3.2
```

# REST API

Ollama has a REST API for running and managing models.

## Generate a response

```shell
curl http://localhost:11434/api/generate -d '{
    "model": "llama3.2",
    "prompt":"Why is the sky blue?"
}'
```

## Chat with a model

```shell
curl http://localhost:11434/api/chat -d '{
"model": "llama3.2",
    "messages": [
      { "role": "user", "content": "why is the sky blue?" }
    ]
}'
```

See the API documentation for all endpoints.