# CLAUDE.md

このファイルはClaude Code (claude.ai/code) がこのリポジトリで作業する際のガイダンスを提供します。

## プロジェクト概要

Ysato.SpectatorはOpenAPI仕様のカバレッジを検証するPHPパッケージで、どのAPIエンドポイントがテストされているかを追跡します。テスト実行を監視し、テストされたエンドポイントをOpenAPI仕様と比較して、テストされていないシナリオを特定します。

### コアアーキテクチャ

このパッケージはシンプルで効果的な設計に従っています：

- **Spectator**: 検証プロセスを管理し、結果を追跡するシングルトンクラス
- **OpenApiSpec**: OpenAPI仕様ファイルのラッパー、パス解決とシーン生成を処理
- **Scene**: 単一のAPIシナリオ（メソッド + パス + ステータスコードの組み合わせ）を表現
- **Result**: カバレッジを追跡するためのImplemented/NotImplemented状態を持つ抽象結果型
- **SpectatorExtension**: テスト実行と統合するPHPUnit拡張
- **ResultRenderer**: カバレッジ結果を表示するコンソール出力レンダラー

### 主要コンセプト

- **Scenes**: OpenAPI仕様のHTTPメソッド、パス、ステータスコードの各組み合わせが「シーン」になる
- **Spectation**: テスト中に`spectate()`を呼び出してシーンを実装済みとしてマークする行為
- **Coverage Tracking**: すべてのシーンはNotImplementedから開始し、spectateされるとImplementedになる

## 開発コマンド

### テスト
```bash
# カバレッジなしでテストを実行
composer test

# カバレッジありでテストを実行（Xdebug必須）
composer coverage

# カバレッジありでテストを実行（PCOV必須）
composer pcov

# スペクテーターモードで実行（APIカバレッジを表示）
composer spectate
# または
RENDER_SPECTATOR_RESULT=true ./vendor/bin/phpunit --no-progress --no-results
```

### コード品質
```bash
# コーディング規約をチェック
composer cs

# コーディング規約の違反を修正
composer cs-fix

# 静的解析を実行（phpmd + phpstan + psalm）
composer qa

# リンティングと静的解析の両方を実行
composer lints

# 完全なテストスイートを実行（lints + tests）
composer tests
```

### Docker開発
```bash
# Dockerイメージをビルド
just build

# Docker経由でcomposerコマンドを実行
just composer install
just composer update

# GitHub Actionsをローカルで実行
just act

# Dockerイメージをクリーン
just clean
```

## 設定ファイル

- **phpunit.xml**: テスト実行のためのPHPUnit設定
- **phpcs.xml**: コードスタイルのためのPHP CodeSnifferルール
- **phpmd.xml**: 静的解析のためのPHP Mess Detectorルール
- **phpstan.neon**: 静的解析のためのPHPStan設定
- **psalm.xml**: 静的解析のためのPsalm設定
- **openapi.yaml**: テスト用のサンプルOpenAPI仕様
- **justfile**: DockerワークフローのためのJustコマンドランナータスク

## OpenAPI統合

このパッケージはOpenAPI仕様（YAML形式）を読み取り、すべてのエンドポイント/メソッド/ステータスの組み合わせに対してシーンを生成します。`GetsOpenApiSpecPath`トレイトを実装して、カスタム仕様ファイルの場所を指定できます。デフォルトの仕様パスは`OPENAPI_SPEC_PATH`環境変数によって決定されます。

## テスト統合

テストでSpectatorを使用するには、APIエンドポイントをテストする際にHTTPメソッド、実際のパス、ステータスコードを指定して`spectate()`メソッドを呼び出します。`RENDER_SPECTATOR_RESULT`環境変数がtrueに設定されている場合、PHPUnit拡張が自動的に結果をレンダリングします。