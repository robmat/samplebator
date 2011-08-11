#import <UIKit/UIKit.h>
#import "GData.h"

@interface VideoCell : UITableViewCell {
	IBOutlet UILabel* dateLbl;
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* durationLbl;
	IBOutlet UIImageView* imageCategory;
	GDataEntryYouTubeVideo* entry;
}

@property (nonatomic, retain) IBOutlet UILabel* dateLbl;
@property (nonatomic, retain) IBOutlet UILabel* titleLbl;
@property (nonatomic, retain) IBOutlet UILabel* durationLbl;
@property (nonatomic, retain) IBOutlet UIImageView* imageCategory;
@property (nonatomic, retain) GDataEntryYouTubeVideo* entry;

- (id) initializeWithGData: (GDataEntryYouTubeVideo*) entry;

@end
