import subprocess

with open('.env') as f:
    for line in f:
        line = line.strip()
        if line and not line.startswith('#'):
            subprocess.run(f"heroku config:set {line}", shell=True)
