install:
	rm -rf build
	mkdir build
	find "site" -depth -type d -print0 | sort -z | xargs -0 -I{} mkdir ./build/{}
	find "site" -depth -type f -iname '*.php' -exec cp {} ./build/{} \;
	find "media" -depth -type d -print0 | sort -z | xargs -0 -I{} mkdir ./build/{}
	find "media" -depth -type f -exec cp {} ./build/{} \;
	cp install.xml ./build/install.xml
	cp install.sql ./build/install.sql
	cd ./build ;\
	zip -r -9 com_beursplein.zip *

