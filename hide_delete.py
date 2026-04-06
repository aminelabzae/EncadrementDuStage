import os
import glob
import re

base_dir = r"c:\Users\aminelabzae\OneDrive\Documents\Programmation\2em-annee\laravel\Stages_Encadrement\resources\views"

pattern = re.compile(r'(<form[^>]*>(?:(?!</?form>).)*?@method\(\'DELETE\'\).*?</form>)', re.DOTALL | re.IGNORECASE)

for filepath in glob.glob(os.path.join(base_dir, "**", "*.blade.php"), recursive=True):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    def repl(m):
        form_content = m.group(1)
        # Avoid double wrapping if already wrapped
        if "@if(!Auth::user()->hasRole('STAGIAIRE'))" in content:
            # Let's just do a naive check if the exact form string was already wrapped but it's hard without parsing.
            pass
        return f"@if(!Auth::user()->hasRole('STAGIAIRE'))\n{form_content}\n@endif"
        
    new_content, count = pattern.subn(repl, content)
    
    if count > 0:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath} ({count} replacements)")
