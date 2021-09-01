
str = ""

with open("Of course, in one sense.txt", "r", encoding="utf8") as a_file:
    for line in a_file:
        stripped_line = line.strip()
        str += stripped_line

a_file.close()

reverse = ' '.join(reversed(str.split(' ')))

with open("content.txt","w",encoding="utf8") as file:
    file.write(reverse)

file.close()

print("finished")