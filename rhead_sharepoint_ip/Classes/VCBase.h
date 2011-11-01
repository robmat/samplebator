#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>
#import <MessageUI/MessageUI.h>

@interface VCBase : UIViewController <MFMailComposeViewControllerDelegate> {
	BOOL shouldIPlayPlak;
	AVAudioPlayer* avPlayer;
	UIButton* backBtn;
}

- (void)playPlak;
- (void)animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance;
- (void)hideBackBtn;
- (void)setUpTabBarButtons;
- (void)infoAction: (id) sender;
- (void)newsAction: (id) sender;
- (void)mailAction: (id) sender;
@end
