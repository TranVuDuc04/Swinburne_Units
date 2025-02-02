def is_leap(year):
    leap = True  
    if year % 4 != 0 or year % 100 == 0:
        leap = False
    elif year % 400 == 0:
        leap = True
    return leap

print(is_leap(int(input("Enter the year: "))))
