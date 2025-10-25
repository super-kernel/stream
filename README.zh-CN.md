# super-kernel/message

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-orange)](../LICENSE)
[![Code Style](https://img.shields.io/badge/code%20style-PSR--12-lightgrey)](https://www.php-fig.org/psr/psr-12/)

## 介绍

> **super-kernel/message** 是一个针对 `PHP` + `Swoole` 的数据流包装器组件，旨在规范并尽可能提供完善的
> 请求/响应数据流处理能力。该组件提供了针对不同网络协议与客户端的数据流封装接口，方便在高性能网络应用中统一处理各种数据交互。

## 特性

- [x] 提供了适用于 `HTTP`、`TCP`、`UDP`、`WebSocket`、`物联网`等协议的请求/响应流包装器
- [x] 支持 `文件上传`、`文件流`、`Swoole 流` 等多种数据流类型
- [x] 针对物联网协议和其他自定义协议提供扩展接口
- [x] 基于 `Swoole` 扩展，充分利用高性能协程特性
- [x] 可与 `Super-Kernel` 框架 无缝集成

# 安装

使用 Composer 安装：

```bash
composer require super-kernel/message
```

> 注意：本组件仅在安装了 Swoole 扩展的环境下可用。

## 贡献

欢迎提交 Issue 或 Pull Request。 请确保遵循 PSR-12 编码规范，并在修改前运行单元测试。

## 📜 许可证

本项目基于 MIT License 开源。