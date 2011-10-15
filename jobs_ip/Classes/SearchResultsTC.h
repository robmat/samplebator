#import <UIKit/UIKit.h>

@interface SearchResultsTC : UITableViewCell {
	IBOutlet UILabel* titleLbl;
	IBOutlet UILabel* salaryLbl;
	IBOutlet UILabel* descLbl;
	IBOutlet UIButton* redSignImage;
	id delegate;
	NSString* jobId;
}

@property(nonatomic, retain) IBOutlet UILabel* titleLbl;
@property(nonatomic, retain) IBOutlet UILabel* salaryLbl;
@property(nonatomic, retain) IBOutlet UILabel* descLbl;
@property(nonatomic, retain) IBOutlet UIButton* redSignImage;
@property(nonatomic, retain) id delegate;
@property(nonatomic, retain) NSString* jobId;

- (void) redButtonAction: (id) sender;

@end
