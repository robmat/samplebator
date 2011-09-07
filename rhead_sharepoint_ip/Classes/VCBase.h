#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>

@interface VCBase : UIViewController {
	BOOL shouldIPlayPlak;
	AVAudioPlayer* avPlayer;
	UIButton* backBtn;
}

- (void)playPlak;
- (void) animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance;
- (void) hideBackBtn;
@end
