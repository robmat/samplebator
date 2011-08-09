#import <UIKit/UIKit.h>
#import "AVPlayerView.h"
#import "GData.h"
#import "VCBase.h"

@interface UploadVideoMenuVC : VCBase {
	AVPlayer *avplayer;
	IBOutlet AVPlayerView *playerView;
	UITextField* tagsText;
	UITextField* noteText;
	GDataServiceGoogleYouTube* ytService;
	NSString* educationCategory;
	IBOutlet UIProgressView* progressView;
}

@property (nonatomic, retain) AVPlayer *avplayer;
@property (nonatomic, retain) IBOutlet AVPlayerView *playerView;
@property (nonatomic, retain) GDataServiceGoogleYouTube* ytService;
@property (nonatomic, retain) NSString* educationCategory;
@property (nonatomic, retain) IBOutlet UIProgressView* progressView;

- (IBAction) aboutAction: (id) sender;
- (IBAction) addNoteAction: (id) sender;
- (IBAction) addTagsAction: (id) sender;
- (IBAction) submittAction: (id) sender;
- (void)fetchStandardCategories;
@end
