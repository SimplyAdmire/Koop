# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Fixed
- Skip page if XML can not be parsed by `simplexml_load_string()`
- Prevent "argument 5 passed to Publication should be instanceof DateTime" if publication date
  can not be parsed.
- Use `&keyword all <terms>` for fulltext search to support multiple terms 
- Leave encoding to http_build_query but wrap constraints

## [0.1.1] - 2018-11-25
### Fixed
- Urlencode query arguments
  This allows spaces in search terms or organisation names like `Bergen (NH)`

## [0.1.0] - 2018-09-06
### Changed
- Implement Countable on QueryResult

