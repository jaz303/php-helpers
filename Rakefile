PHP = ENV['PHP'] || "/usr/local/bin/php"
SOURCES = %w(primitive preamble support array_path query_string asset tag form)
SOURCES_5_3 = %w(functional)

OUTPUT = {
  'helpers.php' => SOURCES,
  'helpers-5.3.php' => SOURCES + SOURCES_5_3
}

def synthesize(php_file, sources)
  file php_file => sources.map { |s| "src/#{s}.php"} do
    File.open(php_file, 'w') do |out|
      out.write("<?php\n")
      sources.each do |src|
        in_php = false
        File.open('src/' + src + '.php').each do |line|
          if line =~ /^<\?php/
            in_php = true
          elsif line =~ /^\?>/
            in_php = false
          elsif in_php
            out.write(line)
          end
        end
      end
      out.write("?>\n")
    end
  end
end

OUTPUT.each { |php_file, sources| synthesize(php_file, sources) }

task :build => OUTPUT.keys

task :clean do
  OUTPUT.keys.each { |k| FileUtils.rm_f(k) }
end

task :test => 'helpers-5.3.php' do
  sh "#{PHP} run_tests.php"
end
