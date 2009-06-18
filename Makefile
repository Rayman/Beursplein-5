install:
	rm -rf build
	mkdir build
	find "site" -depth -type d -print0 | sort -z | xargs -0 -I{} mkdir ./build/{}
	find -depth -type f -iname '*.php' -exec cp {} ./build/{} \;

