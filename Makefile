install:
	rm -rf build
	mkdir build
	find "site" -depth -type d -print0 | sort -z | xargs -0 -I{} mkdir ./build/{}
	find "site" -depth -type f -iname '*.php' -exec cp {} ./build/{} \;
	cp -r media build/media
	cp -r admin build/admin
	cp install.xml build/install.xml
	cd ./build ;\
    7z a -mx9 -tzip com_beursplein *

