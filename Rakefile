SOURCES = %w(preamble support array_path asset tag form)

task :compile do
  File.open('helpers.php', 'w') do |out|
    out.write("<?php\n")
    SOURCES.each do |src|
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