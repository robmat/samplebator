#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>
#import "AVPlayerView.h"

@interface ChooseCategoryVC : UIViewController <UIPickerViewDataSource, UIPickerViewDelegate> {
	AVPlayer *avplayer;
	IBOutlet AVPlayerView *playerView;
	IBOutlet UIPickerView* categoryPicker;
	NSString* category;
}

@property (nonatomic, retain) AVPlayer *avplayer;
@property (nonatomic, retain) IBOutlet AVPlayerView *playerView;
@property (nonatomic, retain) IBOutlet UIPickerView* categoryPicker;
@property (nonatomic, retain) NSString* category;

- (IBAction) okAction: (id) sender;
- (IBAction) cancelAction: (id) sender;

@end
