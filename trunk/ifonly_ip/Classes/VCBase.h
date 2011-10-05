#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>
#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>
#import <MessageUI/MFMailComposeViewController.h>

@interface VCBase : UIViewController <MFMailComposeViewControllerDelegate> {
	BOOL shouldIPlayPlak;
	AVAudioPlayer* avPlayer;
	UIButton* backBtn;
}

- (void)playPlak;
- (void)animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance;
- (IBAction)aboutAction: (id) sender;
- (IBAction)categoriesAction: (id) sender;
- (IBAction)feedbackAction: (id) sender;

@end
