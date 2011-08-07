#import <UIKit/UIKit.h>
#import "AVPlayerView.h"
#import "GData.h"

@interface UploadVideoMenuVC : UIViewController {
	AVPlayer *avplayer;
	IBOutlet AVPlayerView *playerView;
	UITextField* tagsText;
	UITextField* noteText;
	GDataServiceGoogleYouTube* ytService;
	NSString* educationCategory;
}

@property (nonatomic, retain) AVPlayer *avplayer;
@property (nonatomic, retain) IBOutlet AVPlayerView *playerView;
@property (nonatomic, retain) GDataServiceGoogleYouTube* ytService;
@property (nonatomic, retain) NSString* educationCategory;

- (IBAction) aboutAction: (id) sender;
- (IBAction) addNoteAction: (id) sender;
- (IBAction) addTagsAction: (id) sender;
- (IBAction) submittAction: (id) sender;

@end
