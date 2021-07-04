# Dependencies
- docker

OR
- php 7.4+
- bash

# Usage

Clone the repo
```
git clone https://github.com/tristanveness/date-test date-test-tv
cd date-test-tv
```

If you have docker, but don't have php/bash installed locally
```
docker run -ti --rm -v $(pwd):/date-test -w /date-test php:7.4-cli bash
```

Then
```
cat thoughts.txt
./test.sh
# Solution A
./daysBetweenA <date1> <date2>
# Solution B
./daysBetweenB <date1> <date2>
```
