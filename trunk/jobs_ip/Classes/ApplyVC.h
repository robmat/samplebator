#import <UIKit/UIKit.h>
#import "UIPlaceHolderTextView.h"
#import "VCBase.h"

@interface ApplyVC : VCBase <UIAlertViewDelegate> {
	
	IBOutlet UIPlaceHolderTextView* textView;
	NSString* jobId;
	IBOutlet UILabel* jobTitle;
	IBOutlet UILabel* cvId;
	IBOutlet UILabel* titlLbl;
	IBOutlet UILabel* dateLbl;
	IBOutlet UILabel* descLbl;
}

@property(nonatomic,retain) IBOutlet UIPlaceHolderTextView* textView;
@property(nonatomic,retain) NSString* jobId;
@property(nonatomic,retain) IBOutlet UILabel* jobTitle;
@property(nonatomic,retain) IBOutlet UILabel* cvId;
@property(nonatomic,retain) IBOutlet UILabel* titlLbl;
@property(nonatomic,retain) IBOutlet UILabel* dateLbl;
@property(nonatomic,retain) IBOutlet UILabel* descLbl;

- (void)applyAction: (id) sender;

@end
