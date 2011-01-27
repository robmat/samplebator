//
//  editAdressViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 10/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editAdressViewController : UIViewController {

	IBOutlet UITextField * addressField ;

}

-(IBAction)saveAction:(id)sender ;
-(NSString *) dataFilePath ;


@end
