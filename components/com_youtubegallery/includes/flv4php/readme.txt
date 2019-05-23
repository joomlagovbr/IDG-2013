Go to http://fanno.dk/

If you have any question's or find tutorials

Key features:
# Enhansed streaming security. Prevent most streaming tools from downloading clips.
# Extract and/or modify metadata runtime
# Output Preview and Thumb
# Change playback rate of Preview's
# Analyze (Metadata Frames / Video Frames / Audio Frames)


Feature Features:
Adding onMetaData inside the movie to use for stuff like subtitels, ect. (If posable)
Adding Suport for playback rate change in fulltime movies or area of it


Change Log:
------24/08-2007------
Fix: Fixed issue with missing timestamps.

------16/04-2007------
Fixed: Minor errors

------18/02-2007------
Added: Added Support for vp6 codec with alpha level (codec id 5)

------25/01-2007------
Added: getid3 added for better video info extracting. (Modifyed version with few "bugs" solved),
		Founed in (array)$flv->FileInfo[].
Fixed: Codec issue with on2 6.
Fixed: Error with some files.
Added: Playback rate change for Preview.
Added: Verious Tag variabls like codec_name.
Fixed: Frame timestamps for thumb / preview.


------14/01-2007------
Fixed: bytesTotal returnering -1
Change: getFlvThumb() and getFlvPreview() , now return the flv in sted so it ca be used to,
		save to hard disk.
Added: metadata to getFlvThumb() and getFlvPreview(), so they can be used for properties like,
		height/width can be used from Flash and so on ...
----------------------