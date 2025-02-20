# FileComparator

FileComparator is a PHP utility that compares two lexicographically sorted files and generates two output files:

- **output1.txt**: Contains the strings (or characters) found only in the first file.
- **output2.txt**: Contains the strings (or characters) found only in the second file.

The utility supports two input formats:
- **Multi-line Format**: Each string is on a separate line.
- **Single-line Format**: The file contains a single line; each character is treated as an individual entry.

## Requirements

- PHP 8.3.17 or later
- Composer

## Installation

1. Clone the repository.
2. Navigate to the project directory.
3. Install dependencies with:
   ```bash
   composer install

### Usage
Run the comparator from the command line by providing two input files:

```bash
php bin/compare input1.txt input2.txt
```

* output1.txt (unique entries from the first file)
* output2.txt (unique entries from the second file)

### Running Tests
The project includes PHPUnit tests covering multiple scenarios:

1. Multi-line input 
2. Single-line input 
3. Edge cases with symbols, numbers, and mixed characters 
4. Handling duplicates and non-overlapping strings

Run the tests with:

```bash
php vendor/bin/phpunit
```
