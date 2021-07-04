# Usage

```

# (If you have docker, but don't have php/bash installed locally)
docker run -ti --rm -v $(pwd):/date-test -w /date-test php:7.4-cli bash
cat thoughts.txt
./test.sh
# Solution A
./daysBetween <date1> <date2>
# Solution B
./daysBetween2 <date1> <date2>
```
