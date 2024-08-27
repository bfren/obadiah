use bf
bf env load

def main [] {
    # generate empty config
    let config = bf env OBADIAH_CONFIG
    if (bf env check OBADIAH_GENERATE_EMPTY_CONFIG) and ($config | bf fs is_not_file) {
        cp /www/config-sample.yml $config
    }
}
