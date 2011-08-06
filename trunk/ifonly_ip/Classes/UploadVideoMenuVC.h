#import <UIKit/UIKit.h>
#import "AVPlayerView.h"

@interface UploadVideoMenuVC : UIViewController {
	AVPlayer *avplayer;
	IBOutlet AVPlayerView *playerView;
	UITextField* tagsText;
	UITextField* noteText;
}

@property (nonatomic, retain) AVPlayer *avplayer;
@property (nonatomic, retain) IBOutlet AVPlayerView *playerView;

- (IBAction) aboutAction: (id) sender;
- (IBAction) addNoteAction: (id) sender;
- (IBAction) addTagsAction: (id) sender;
- (IBAction) submittAction: (id) sender;

@end
