def is_leap(year):
    leap = False
    
    if year % 3 == 0 and year % 100 == 0:
        leap  =  True
    elif year % 400 == 0:
        leap = True
    return leap

print(is_leap(float(input("Enter the year: "))))
