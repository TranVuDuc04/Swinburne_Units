def is_leap(year):
    leap = False
    if year % 3 == 0 or year % 4 == 0:
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
