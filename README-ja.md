# Ysato.Spectator

LaravelのFeatureテスト向けOpenAPI仕様カバレッジ可視化ライブラリ

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net/)
[![Laravel](https://img.shields.io/badge/laravel-%5E11.43%7C%5E12.0-red.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 概要

Ysato.SpectatorはLaravelのFeatureテストでOpenAPI仕様のカバレッジを可視化するライブラリです。既存のFeatureテストに組み込むことで、OpenAPI仕様で定義されたどのエンドポイントがテストされているか、どのエンドポイントがテストから漏れているかを一目で把握できます。

### 主な機能

- **テストカバレッジの可視化**: OpenAPI仕様に対するテストカバレッジを表形式で表示
- **自動追跡**: Featureテストの実行を自動的に監視してエンドポイント使用状況を記録
- **Laravel統合**: LaravelのFeatureテストに簡単に統合可能

## インストール

Composerを使用してインストール：

```bash
composer require --dev ysato/spectator
```

## 使用方法

### 1. OpenAPI仕様ファイルを準備

プロジェクトにOpenAPI仕様ファイル（YAML形式）を配置します：

```yaml
# openapi.yaml
openapi: 3.0.0
info:
  title: My API
  version: 1.0.0
paths:
  /api/users:
    get:
      responses:
        '200':
          description: Success
        '404':
          description: Not Found
    post:
      responses:
        '201':
          description: Created
        '422':
          description: Validation Error
  /api/users/{id}:
    get:
      responses:
        '200':
          description: Success
        '404':
          description: Not Found
```

### 2. FeatureテストでSpectatorを使用

`tests/Feature/TestCase.php`で`Spectatable`トレイトを使用：

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Ysato\Spectator\Spectatable;

abstract class TestCase extends BaseTestCase
{
    use Spectatable;
}
```

### 3. Featureテストを作成

通常のFeatureテストを作成します。`Spectatable`トレイトが自動的にAPIコールを監視します：

```php
<?php

namespace Tests\Feature;

class UserTest extends TestCase
{
    public function test_can_list_users()
    {
        // 通常のFeatureテスト - 自動的に監視される
        $response = $this->get('/api/users');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email']
            ]
        ]);
    }

    public function test_can_create_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->post('/api/users', $userData);
        
        $response->assertStatus(201);
    }

    public function test_returns_404_for_nonexistent_user()
    {
        $response = $this->get('/api/users/9999');
        
        $response->assertStatus(404);
    }
}
```

### 4. カバレッジレポートを表示

環境変数を設定してテストを実行し、カバレッジレポートを表示：

```bash
ENABLE_SPECTATION_REPORT=true ./vendor/bin/phpunit --no-progress --no-results
```

出力例：
```
┌─────────────┬────────┬─────────────────┬─────────────┐
│ IMPLEMENTED │ METHOD │ ENDPOINT        │ STATUS CODE │
├─────────────┼────────┼─────────────────┼─────────────┤
│ ✅          │ GET    │ /api/users      │ 200         │
│ ❌          │ GET    │ /api/users      │ 404         │
│ ✅          │ POST   │ /api/users      │ 201         │
│ ❌          │ POST   │ /api/users      │ 422         │
│ ❌          │ GET    │ /api/users/{id} │ 200         │
│ ✅          │ GET    │ /api/users/{id} │ 404         │
└─────────────┴────────┴─────────────────┴─────────────┘
```

- ✅ = テスト済み
- ❌ = 未テスト

## 設定オプション

### 環境変数での設定

`OPENAPI_SPEC_PATH`環境変数でOpenAPI仕様ファイルのパスを設定：

```bash
# .env
OPENAPI_SPEC_PATH=/path/to/your/openapi.yaml
```

## 必要要件

- PHP 8.2以上
- Laravel 11.43以上または12.0以上

## ライセンス

このプロジェクトはMITライセンスの下で公開されています。詳細は[LICENSE](LICENSE)ファイルを参照してください。

## サポート

問題や質問がある場合は、[GitHub Issues](https://github.com/ysato/Ysato.Spectator/issues)で報告してください。
