# Game Backend API

This is a PHP/MySQL backend API for a Unity game with player management and ranking system.

## Requirements

- Docker
- Docker Compose

## Setup

1. Clone the repository
2. Navigate to the project directory
3. Run Docker Compose:
```bash
docker-compose up -d
```

4. Import the database schema:
```bash
docker exec -i be-game-bahasaku_mysql_1 mysql -u gameuser -pgamepass game_db < src/database/schema.sql
```

## API Endpoints

### Players

- Create: POST /api/player/create.php
- Read All: GET /api/player/read.php
- Read One: GET /api/player/read_one.php?id=1
- Update: PUT /api/player/update.php
- Delete: DELETE /api/player/delete.php

### Game Rankings

- Create: POST /api/ranking/create.php
- Read All: GET /api/ranking/read.php
- Read One: GET /api/ranking/read_one.php?id=1
- Update: PUT /api/ranking/update.php
- Delete: DELETE /api/ranking/delete.php

## API Usage Examples

### Create Player
```bash
curl -X POST http://localhost:8080/api/player/create.php \
-H "Content-Type: application/json" \
-d '{
    "username": "player1",
    "email": "player1@example.com",
    "password": "secret123"
}'
```

### Create Ranking
```bash
curl -X POST http://localhost:8080/api/ranking/create.php \
-H "Content-Type: application/json" \
-d '{
    "player_id": 1,
    "score": 1000,
    "level": 5
}'
```

### Get All Rankings
```bash
curl http://localhost:8080/api/ranking/read.php
```

## Unity Integration

In your Unity project, you can use UnityWebRequest to communicate with these endpoints. Example:

```csharp
using UnityEngine;
using UnityEngine.Networking;
using System.Collections;

public class APIClient : MonoBehaviour
{
    private string baseUrl = "http://localhost:8080/api";

    public IEnumerator CreatePlayer(string username, string email, string password)
    {
        string url = baseUrl + "/player/create.php";
        string json = JsonUtility.ToJson(new {
            username = username,
            email = email,
            password = password
        });

        using (UnityWebRequest request = UnityWebRequest.Post(url, json))
        {
            request.SetRequestHeader("Content-Type", "application/json");
            yield return request.SendWebRequest();

            if (request.result == UnityWebRequest.Result.Success)
            {
                Debug.Log("Player created successfully");
            }
            else
            {
                Debug.Log("Error: " + request.error);
            }
        }
    }

    public IEnumerator SubmitScore(int playerId, int score, int level)
    {
        string url = baseUrl + "/ranking/create.php";
        string json = JsonUtility.ToJson(new {
            player_id = playerId,
            score = score,
            level = level
        });

        using (UnityWebRequest request = UnityWebRequest.Post(url, json))
        {
            request.SetRequestHeader("Content-Type", "application/json");
            yield return request.SendWebRequest();

            if (request.result == UnityWebRequest.Result.Success)
            {
                Debug.Log("Score submitted successfully");
            }
            else
            {
                Debug.Log("Error: " + request.error);
            }
        }
    }
}
```

#pull
git pull origin master



curl -X POST https://api.e-loa.id/api/ranking/create.php \
-H "Content-Type: application/json" \
-d '{
    "player_id": 1,
    "score": 1500,
    "level": 12
}'