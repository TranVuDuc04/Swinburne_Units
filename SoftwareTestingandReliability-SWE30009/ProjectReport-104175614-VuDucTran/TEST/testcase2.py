import subprocess

test_years = [1000, 3000, 9000, 27000]

for i in range(1, 31):
    mutant_file = f"../MUTANTS/mutant{i}.py"  

    print(f"\nRunning tests on {mutant_file}")

    for year in test_years:
        result = subprocess.run(
            ['python', mutant_file],  
            input=str(year), text=True, capture_output=True
        )
        print(f"Testing year {year} with {mutant_file}: {result.stdout.strip()}")
