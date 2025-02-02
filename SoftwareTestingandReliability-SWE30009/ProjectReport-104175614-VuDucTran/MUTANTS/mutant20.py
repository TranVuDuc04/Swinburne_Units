def is_leap(year):
    leap = False
    truth_count = (year % 4 == 0) + (year % 100 != 0) + (year % 400 == 0)
    if truth_count > 1:
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
