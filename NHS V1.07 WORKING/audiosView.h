//
//  audiosView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 30/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AVFoundation/AVAudioPlayer.h>


@interface audiosView : UIViewController<UIScrollViewDelegate> {

	IBOutlet UIButton * boton1 ;
	IBOutlet UIButton * boton2 ;
	IBOutlet UIButton * boton3 ;
	IBOutlet UIButton * boton4 ;
	IBOutlet UIButton * boton5 ;
	IBOutlet UIButton * boton6 ;
	IBOutlet UIButton * boton7 ;
	
	BOOL boton1Pressed ;
	BOOL boton2Pressed ;
	BOOL boton3Pressed ;
	BOOL boton4Pressed ;
	BOOL boton5Pressed ;
	BOOL boton6Pressed ;
	BOOL boton7Pressed ;	
	
	IBOutlet UIScrollView * scrollView ;
	
	NSString * path1 ;
	AVAudioPlayer * theAudio1 ;

	NSString * path2 ;
	AVAudioPlayer * theAudio2 ;
	
	NSString * path3 ;
	AVAudioPlayer * theAudio3 ;
	
	NSString * path4 ;
	AVAudioPlayer * theAudio4 ;
	
	NSString * path5 ;
	AVAudioPlayer * theAudio5 ;

	NSString * path6 ;
	AVAudioPlayer * theAudio6 ;	
	
	NSString * path7 ;
	AVAudioPlayer * theAudio7 ;
}

@property (nonatomic, retain) IBOutlet UIScrollView * scrollView ;


-(IBAction)buton1Action ;
-(IBAction)buton2Action ;
-(IBAction)buton3Action ;
-(IBAction)buton4Action ;
-(IBAction)buton5Action ;
-(IBAction)buton6Action ;
-(IBAction)buton7Action ;

-(IBAction)MP3online ;

@end
