#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>

@interface VCBase : UIViewController {
	BOOL shouldIPlayPlak;
	AVAudioPlayer* avPlayer;
@public	
    UIButton* backBtn;
    UIButton* infoBtn;
    UIButton* newsBtn;
    UIButton* contactBtn;
}

- (void)playPlak;
- (void)animateView: (UIView*) uiview up: (BOOL) up distance: (int) movementDistance;
- (void)hideBackBtn;
- (void)setUpTabBarButtons;
- (void)infoAction: (id) sender;
- (void)newsAction: (id) sender;

@end
