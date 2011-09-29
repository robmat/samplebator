#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>

@interface VCBase : UIViewController {
	BOOL shouldIPlayPlak;
	AVAudioPlayer* avPlayer;
	UIButton* backBtn;
	UIButton* tabMainMenuBtn;
}

- (void)playPlak;
- (void)animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance;
- (void)hideBackBtn;
- (void)backAction: (id) sender;
- (void)hideTabButtons;
- (void)setUpTabButtons;
@end
