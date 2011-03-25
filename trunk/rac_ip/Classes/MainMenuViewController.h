//
//  MainMenuViewController.h
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>

@interface MainMenuViewController : UIViewController {
	AVAudioPlayer* theAudio;
}

- (IBAction) mapAction: (id) sender;
- (IBAction) dateAction: (id) sender;

@end
