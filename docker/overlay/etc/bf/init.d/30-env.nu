use bf
bf env load

# Set environment variables
def main [] {
    let data = "/data"
    bf env set CCF_DATA $data
    bf env set CCF_CONFIG $"($data)/config.yml"
}
