use bf
bf env load

def main [] {
    # generate empty config
    let config = bf env CCF_CONFIG
    if (bf env check CCF_GENERATE_EMPTY_CONFIG) and ($config | bf fs is_not_file) {
        cp /www/config-sample.yml $config
    }
}
