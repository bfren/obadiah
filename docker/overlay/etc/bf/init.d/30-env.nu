use bf
bf env load

# Set environment variables
def main [] {
    let data = "/data"
    bf env set OBADIAH_DATA $data
    bf env set OBADIAH_CONFIG $"($data)/config.yml"
}
