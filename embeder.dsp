# Microsoft Developer Studio Project File - Name="embeder" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** DO NOT EDIT **

# TARGTYPE "Win32 (x86) Console Application" 0x0103

CFG=embeder - Win32 Debug console
!MESSAGE This is not a valid makefile. To build this project using NMAKE,
!MESSAGE use the Export Makefile command and run
!MESSAGE 
!MESSAGE NMAKE /f "embeder.mak".
!MESSAGE 
!MESSAGE You can specify a configuration when running NMAKE
!MESSAGE by defining the macro CFG on the command line. For example:
!MESSAGE 
!MESSAGE NMAKE /f "embeder.mak" CFG="embeder - Win32 Debug console"
!MESSAGE 
!MESSAGE Possible choices for configuration are:
!MESSAGE 
!MESSAGE "embeder - Win32 Release console" (based on "Win32 (x86) Console Application")
!MESSAGE "embeder - Win32 Debug console" (based on "Win32 (x86) Console Application")
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
RSC=rc.exe

!IF  "$(CFG)" == "embeder - Win32 Release console"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "embeder___Win32_Release_console"
# PROP BASE Intermediate_Dir "embeder___Win32_Release_console"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release_console"
# PROP Intermediate_Dir "Release_console"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_CONSOLE" /D "_MBCS" /FD /c
# SUBTRACT BASE CPP /YX /Yc /Yu
# ADD CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_CONSOLE" /D "_MBCS" /FD /c
# SUBTRACT CPP /YX /Yc /Yu
# ADD BASE RSC /l 0x40c /d "NDEBUG"
# ADD RSC /l 0x40c /d "NDEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 php5embed.lib php5ts.lib /nologo /subsystem:console /machine:I386 /out:"out/console.exe"
# ADD LINK32 php5embed.lib php5ts.lib /nologo /subsystem:console /machine:I386 /out:"out/console.exe"
# Begin Special Build Tool
SOURCE="$(InputPath)"
PostBuild_Cmds=make_embeder.bat
# End Special Build Tool

!ELSEIF  "$(CFG)" == "embeder - Win32 Debug console"

# PROP BASE Use_MFC 0
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "embeder___Win32_Debug_console"
# PROP BASE Intermediate_Dir "embeder___Win32_Debug_console"
# PROP BASE Ignore_Export_Lib 0
# PROP BASE Target_Dir ""
# PROP Use_MFC 0
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug_console"
# PROP Intermediate_Dir "Debug_console"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_CONSOLE" /D "_MBCS" /FR /FD /GZ /c
# SUBTRACT BASE CPP /YX /Yc /Yu
# ADD CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_CONSOLE" /D "_MBCS" /FR /FD /GZ /c
# SUBTRACT CPP /YX /Yc /Yu
# ADD BASE RSC /l 0x40c /d "_DEBUG"
# ADD RSC /l 0x40c /d "_DEBUG"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 php5embed.lib php5ts.lib /nologo /subsystem:console /debug /machine:I386 /out:"out/console_debug.exe" /pdbtype:sept
# ADD LINK32 php5embed.lib php5ts.lib /nologo /subsystem:console /debug /machine:I386 /out:"out/console_debug.exe" /pdbtype:sept

!ENDIF 

# Begin Target

# Name "embeder - Win32 Release console"
# Name "embeder - Win32 Debug console"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;idl;hpj;bat"
# Begin Source File

SOURCE=.\embeder.cpp
# End Source File
# Begin Source File

SOURCE=.\embeder.rc
# ADD BASE RSC /l 0x40c
# ADD RSC /l 0x40c
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl"
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;rgs;gif;jpg;jpeg;jpe"
# Begin Source File

SOURCE=.\res\php.ico
# End Source File
# End Group
# Begin Group "PHP Files"

# PROP Default_Filter "php;inc"
# Begin Source File

SOURCE=.\res\run.php
# End Source File
# End Group
# Begin Source File

SOURCE=".\php-embed.ini"
# End Source File
# Begin Source File

SOURCE=.\ReadMe.txt
# End Source File
# End Target
# End Project
